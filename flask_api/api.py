from flask import Flask, request, jsonify
from transformers import DistilBertTokenizer, DistilBertForSequenceClassification
import torch
import os
import requests

app = Flask(__name__)

# ✅ Where local files are (from GitHub)
model_dir = "flask_api"

# ✅ Google Drive model download info
model_file_id = "1PxFyjeBWb28HPC-sUJ0lu3TxpT4GRobW"  # 🔥 PUT your actual model.safetensors FILE ID here
model_filename = "model.safetensors"
model_filepath = os.path.join(model_dir, model_filename)

# ✅ Function to download model.safetensors
def download_model_file():
    if not os.path.exists(model_dir):
        os.makedirs(model_dir)  # ✅ Create the directory if it doesn't exist

    if not os.path.exists(model_filepath):
        print(f"Downloading {model_filename} from Google Drive...")
        url = f"https://drive.google.com/uc?export=download&id={model_file_id}"
        response = requests.get(url)
        with open(model_filepath, "wb") as f:
            f.write(response.content)
        print(f"Downloaded {model_filename}")
    else:
        print(f"{model_filename} already exists.")


# ✅ Download model file if needed
download_model_file()

# ✅ Load tokenizer and model
tokenizer = DistilBertTokenizer.from_pretrained(model_dir, local_files_only=True)
model = DistilBertForSequenceClassification.from_pretrained(model_dir, local_files_only=True)

# ✅ Class labels based on your dataset
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

        # ✅ Input validation
        if not responses or len(responses) < 5:
            return jsonify({"error": "Invalid input. Expected at least 5 responses."}), 400

        # ✅ Combine all responses
        combined_text = " ".join(responses)

        # ✅ Tokenize the input text
        inputs = tokenizer(combined_text, return_tensors="pt", truncation=True, padding=True, max_length=512)

        # ✅ Predict with model (no gradient needed)
        with torch.no_grad():
            outputs = model(**inputs)
            logits = outputs.logits

        # ✅ Get predicted class
        predicted_class_id = torch.argmax(logits, dim=-1).item()
        predicted_condition = class_labels.get(predicted_class_id, "Unknown Condition")

        # ✅ Return prediction and confidence score
        return jsonify({
            "predicted_condition": predicted_condition,
            "confidence_score": round(torch.softmax(logits, dim=-1)[0][predicted_class_id].item() * 100, 2)
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)  # Gunicorn will take over in production
