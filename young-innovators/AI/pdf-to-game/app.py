import os
import time
import pdfplumber
from dotenv import load_dotenv
from flask import Flask, request, jsonify
from flask_cors import CORS
from openai import OpenAI

load_dotenv()

API_KEY = os.getenv("OPENAI_KEY_PROVIDER")
if not API_KEY:
    raise SystemExit("Missing OPENAI_KEY_PROVIDER in .env")

client = OpenAI(api_key=API_KEY)

app = Flask(__name__)
CORS(app)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
UPLOAD_FOLDER = os.path.join(BASE_DIR, "uploads")
os.makedirs(UPLOAD_FOLDER, exist_ok=True)


def extract_text_from_pdf(pdf_path):
    text = ""
    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            page_text = page.extract_text()
            if page_text:
                text += page_text + "\n"
    return text.strip()


def generate_csv_from_text(pdf_text, game_type, difficulty, skills, badges):
    prompt = f"""
You are generating educational game content from lesson material.

Return ONLY valid CSV.
Do not return markdown.
Do not return explanations.
Do not return code fences.

Game Type: {game_type}
Difficulty: {difficulty}
Skills: {skills}
Badges: {badges}

Generate EXACTLY 21 questions.

Difficulty structure:
Rows 1-7 = easy
Rows 8-14 = medium
Rows 15-21 = hard

If game_type = boxing, return CSV columns exactly:
question,option1,option2,option3,option4,answer,difficulty

If game_type = escape, return CSV columns exactly:
id,name,theme,question,answer,hint,goal,sky1,sky2,floor,accent,decor,difficulty

Escape rules:
- id starts at 1
- goal must always be get_key
- decor must be one of: space,jungle,ocean,castle
- Use nice room names and themes

General rules:
- Exactly 21 rows
- 7 easy, 7 medium, 7 hard
- Questions must be clear for children aged 10 to 13
- Answers must come from lesson content
- Do not add extra columns

Lesson content:
{pdf_text}
"""

    response = client.responses.create(
        model="gpt-4o-mini",
        input=prompt
    )

    raw = (response.output_text or "").strip()
    raw = raw.replace("```csv", "").replace("```", "").strip()

    if game_type == "boxing":
        if "question,option1" in raw:
            raw = raw[raw.index("question,option1"):]

        if not raw.startswith("question"):
            raw = (
                "question,option1,option2,option3,option4,answer,difficulty\n"
                + raw
            )

    else:
        if "id,name" in raw:
            raw = raw[raw.index("id,name"):]

        if not raw.startswith("id,name"):
            raw = (
                "id,name,theme,question,answer,hint,goal,sky1,sky2,floor,accent,decor,difficulty\n"
                + raw
            )

    return raw


@app.route("/upload", methods=["POST"])
def upload_pdf():
    if "file" not in request.files:
        return jsonify({"status": "error", "message": "No PDF uploaded"}), 400

    file = request.files["file"]

    game_type = request.form.get("game_type", "escape")
    difficulty = request.form.get("difficulty", "Easy")
    skills = request.form.get("skills", "")
    badges = request.form.get("badges", "")

    if file.filename == "":
        return jsonify({"status": "error", "message": "No selected file"}), 400

    if not file.filename.lower().endswith(".pdf"):
        return jsonify({"status": "error", "message": "Only PDF files are allowed"}), 400

    filename = f"{int(time.time())}_{file.filename}"
    pdf_path = os.path.join(UPLOAD_FOLDER, filename)
    file.save(pdf_path)

    try:
        pdf_text = extract_text_from_pdf(pdf_path)

        if not pdf_text:
            return jsonify({
                "status": "error",
                "message": "Could not extract text from PDF"
            }), 400

        csv_output = generate_csv_from_text(
            pdf_text,
            game_type,
            difficulty,
            skills,
            badges
        )

        csv_filename = f"{game_type}_{int(time.time())}.csv"
        generated_folder = os.path.join(BASE_DIR, "..", "generated")
        os.makedirs(generated_folder, exist_ok=True)

        csv_path = os.path.join(generated_folder, csv_filename)

        with open(csv_path, "w", encoding="utf-8") as f:
            f.write(csv_output)

        game_pages = {
            "escape": "EscapeRoomGame.html",
            "snake": "SnakeGame.html",
            "fill": "Fill_the_Blank.html",
            "boxing": "BoxingGame.html"
        }

        return jsonify({
            "status": "success",
            "message": "Game generated successfully",
            "game_page": game_pages.get(game_type, "EscapeRoomGame.html"),
            "csv_file": csv_filename,
            "game_type": game_type
        })

    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500


if __name__ == "__main__":
    app.run(debug=True, use_reloader=False, port=5002)