import spacy
import logging
import json
import sys

logging.basicConfig(level=logging.DEBUG)

def process_tesseract_data(tesseract_output):
    nlp = spacy.load("fr_core_news_sm")
    doc = nlp(tesseract_output)

    processed_data = {}

    # Parcourt les phrases du texte
    for sentence in doc.sents:
        logging.debug(f"Analyzing sentence: {sentence.text}")

        words = [token.text for token in sentence]

        # Recherche du symbole Euro
        for k, word in enumerate(words):
            if "€" in word:
                # Trouver les chiffres avant le symbole Euro
                if k > 0 and any(char.isdigit() or char == "," for char in words[k - 1]):
                    processed_data[words[k - 1].lower()] = words[k]
                elif k > 1 and any(char.isdigit() or char == "," for char in words[k - 2]):
                    processed_data[words[k - 2].lower()] = words[k]

        # Traitement pour les chiffres associés à "montant", "ttc", "euro" après ":" ou "="
        for i, word in enumerate(words):
            if any(keyword in word.lower() for keyword in ["montant", "total", "ttc", "euro"]):
                # Trouver les chiffres avec une virgule avant ou après le mot-clé
                number_text = ""
                for j in range(i + 1, len(words)):
                    if any(char.isdigit() or char == "," for char in words[j]):
                        number_text += words[j]
                    else:
                        break
                if number_text:
                    processed_data[word.lower()] = number_text

        # Traitement pour les chiffres et mots associés après ":" ou "="
        for i, word in enumerate(words):
            if i < len(words) - 1 and (words[i + 1] == ":" or words[i + 1] == "°" or words[i + 1] == "="):
                # Si un mot est suivi par ":" ou "=", associer les chiffres à ce mot
                number_text = ""
                for j in range(i + 2, len(words)):
                    if words[j].isdigit() or words[j] in (".", "_", "-", " "):
                        number_text += words[j]
                    elif len(words[j]) >= 1 and not words[j].isdigit():
                        # Si le mot a au moins deux lettres et n'est pas un chiffre, l'associer au numéro
                        processed_data[words[j].lower()] = number_text
                        break
                    else:
                        break
                if number_text and len(words[i]) >= 1 and not words[i].isdigit():
                    processed_data[words[i].lower()] = number_text

            elif i < len(words) - 1 and words[i + 1].isdigit():
                # Si un mot est suivi par des chiffres, associer les chiffres à ce mot
                number_text = ""
                for j in range(i + 1, len(words)):
                    if words[j].isdigit() or words[j] in (".", "-", " "):
                        number_text += words[j]
                    elif len(words[j]) >= 2 and not words[j].isdigit():
                        # Si le mot a au moins deux lettres et n'est pas un chiffre, l'associer au numéro
                        processed_data[words[j].lower()] = number_text
                        break
                    else:
                        break
                if number_text and len(words[i]) >= 2 and not words[i].isdigit():
                    processed_data[words[i].lower()] = number_text

    return processed_data

if __name__ == "__main__":
    tesseract_output = sys.argv[1] if len(sys.argv) > 1 else ""
    
    result = process_tesseract_data(tesseract_output)

    print(json.dumps(result))
