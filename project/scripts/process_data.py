import spacy
import logging
import json
import sys
import datetime

logging.basicConfig(level=logging.DEBUG)

def process_tesseract_data(tesseract_output):
    nlp = spacy.load("fr_core_news_sm")
    doc = nlp(tesseract_output)

    processed_data = {}

    for token in doc:
        if "facture" in token.text.lower():
            processed_data["numeroFacture"] = float(token.nbor().text.replace(',', '.'))
        elif "sous-total" in token.text.lower():
            processed_data["sousTotal"] = float(token.nbor().text.replace(',', '.'))
        elif "tva" in token.text.lower():
            processed_data["tva"] = float(token.nbor().text.replace(',', '.'))
        elif "total" in token.text.lower():
            processed_data["total"] = float(token.nbor().text.replace(',', '.'))
        elif "adresse" in token.text.lower():
            numero = token.nbor(1).text
            voie = token.nbor(2).text
            nomvoie = token.nbor(3).text
            ville = token.nbor(4).text
            processed_data["adresseClientAcheteur"] = f"{numero} {voie} {nomvoie} {ville}"
        elif "téléphone" in token.text.lower():
            processed_data["telephoneClientAcheteur"] = token.nbor().text
        elif "client" in token.text.lower():
            prenom = token.nbor(1).text
            nom = token.nbor(2).text
            processed_data["nomClientAcheteur"] = f"{prenom} {nom}"
        elif "année" in token.text.lower():
            date_str = token.nbor(1).text
            # Convert the year string to a datetime object with a default date
            default_date_str = "01 January " + date_str
            date_obj = datetime.datetime.strptime(default_date_str, "%d %B %Y")
            
            processed_data["dateFacturation"] = date_obj
            # Extract year from the date
            year = date_obj.year
            processed_data["anneeFacturation"] = year

    return processed_data

if __name__ == "__main__":
    tesseract_output = sys.argv[1] if len(sys.argv) > 1 else ""
    
    result = process_tesseract_data(tesseract_output)

    # Serialize the result to JSON using ISO format for datetime objects
    json_result = json.dumps(result, default=lambda x: x.isoformat() if isinstance(x, datetime.datetime) else None)

    print(json_result)
