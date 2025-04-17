from flask import Flask, request, jsonify
from transformers import DistilBertTokenizer, DistilBertForSequenceClassification
import torch
import os
import requests

app = Flask(__name__)

# ✅ Set model directory
model_dir = os.path.dirname(__file__)  # /flask_api

# ✅ Set paths
model_filename = "model.safetensors"
model_filepath = os.path.join(model_dir, model_filename)

# ✅ Google Drive download setup
model_file_id = "1AQ8ZH5Fo8YDjKDMJdbFPZMEtIFyp_r_Z"  # <-- Your model file ID

def download_model_file():
    if not os.path.exists(model_filepath):
        print(f"Downloading {model_filename} from Google Drive...")
        url = f"https://drive.google.com/uc?export=download&id={model_file_id}"
        response = requests.get(url)
        if response.status_code == 200:
            with open(model_filepath, "wb") as f:
                f.write(response.content)
            print(f"Downloaded {model_filename}")
        else:
            raise Exception(f"Failed to download model. Status code: {response.status_code}")

# ✅ Download model if missing
download_model_file()

# ✅ Load tokenizer
tokenizer = DistilBertTokenizer.from_pretrained(model_dir, local_files_only=True)

# ✅ Load model
model = DistilBertForSequenceClassification.from_pretrained(
    model_dir,
    local_files_only=True,
    trust_remote_code=True  # 🛡️ just in case
)

# ✅ Class labels
class_labels = {
    0: "Anxiety",
    1: "Attention Issues",
    2: "Depression",
    3: "Eating Disorder",
    4: "PTSD",
    5: "Self-Harm Challenges",
    6: "Stress-Related Disorder",
    7: "Substance Use Disorder"
}

@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.json
        responses = data.get("responses", [])

        if not responses or len(responses) < 5:
            return jsonify({"error": "Invalid input. Expected at least 5 responses."}), 400

        combined_text = " ".join(responses)

        inputs = tokenizer(combined_text, return_tensors="pt", truncation=True, padding=True, max_length=512)

        with torch.no_grad():
            outputs = model(**inputs)
            logits = outputs.logits

        predicted_class_id = torch.argmax(logits, dim=-1).item()
        predicted_condition = class_labels.get(predicted_class_id, "Unknown Condition")

        return jsonify({
            "predicted_condition": predicted_condition,
            "confidence_score": round(torch.softmax(logits, dim=-1)[0][predicted_class_id].item() * 100, 2)
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)
