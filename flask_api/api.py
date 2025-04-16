from flask import Flask, request, jsonify
from transformers import BertTokenizer, BertForSequenceClassification
import torch
import os
import requests

app = Flask(__name__)

# Folder where model files are stored
model_dir = "bert_final"
os.makedirs(model_dir, exist_ok=True)

# Only download model.safetensors since other files are already in the repo
model_file_id = "1kbgkW4BY_U8IwW9e-8VirqpRlzYssJiq"  # Your actual file ID
model_filename = "model.safetensors"
model_filepath = os.path.join(model_dir, model_filename)

def download_model_file():
    if not os.path.exists(model_filepath):
        print(f"Downloading {model_filename} from Google Drive...")
        url = f"https://drive.google.com/uc?export=download&id={model_file_id}"
        response = requests.get(url)
        with open(model_filepath, "wb") as f:
            f.write(response.content)
        print(f"Downloaded {model_filename}")
    else:
        print(f"{model_filename} already exists.")

# Download model file if missing
download_model_file()

# Load tokenizer and model
tokenizer = BertTokenizer.from_pretrained(model_dir, local_files_only=True)
model = BertForSequenceClassification.from_pretrained(model_dir, local_files_only=True)

# Class labels
class_labels = {
    0: "Anxiety",
    1: "Depression",
    2: "PTSD",
    3: "Stress-Related Disorder",
    4: "Substance Use Disorder",
    5: "Eating Disorder",
    6: "Self-Harm Challenges",
    7: "Attention Issues"
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
