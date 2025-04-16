from flask import Flask, request, jsonify
from transformers import BertTokenizer, BertForSequenceClassification
import torch
import os
import requests

app = Flask(__name__)

# Define model directory
model_dir = "bert_model"
os.makedirs(model_dir, exist_ok=True)

# Google Drive file IDs
model_files = {
    "config.json": "1MWU5hL38YuywDM1CFnabEhRrMQME39V9",
    "model.safetensors": "1kbgkW4BY_U8IwW9e-8VirqpRlzYssJiq",
    "special_tokens_map.json": "1-ObUREY47SF2QIlLWBhoNrjmxngAjsym",
    "tokenizer_config.json": "1SwltBONE0lWghkSlZwGA3Z-ygNa63kNo",
    "vocab.txt": "1Th-WorHC_TtgrG7uNBtdSs54-4n9VcAz"
}

# Download model files if not present
def download_from_drive(filename, file_id):
    filepath = os.path.join(model_dir, filename)
    if not os.path.exists(filepath):
        print(f"Downloading {filename}...")
        url = f"https://drive.google.com/uc?export=download&id={file_id}"
        response = requests.get(url)
        with open(filepath, "wb") as f:
            f.write(response.content)

# Download all required files
for fname, fid in model_files.items():
    download_from_drive(fname, fid)

# Load model and tokenizer
tokenizer = BertTokenizer.from_pretrained(model_dir, local_files_only=True)
model = BertForSequenceClassification.from_pretrained(model_dir, local_files_only=True)

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
