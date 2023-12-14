# process_data.py
import spacy

def process_tesseract_data(tesseract_output):
    # Utilise spaCy pour traiter les données Tesseract
    nlp = spacy.load("fr_core_news_sm")
    doc = nlp(tesseract_output)

    # Initialise les variables pour stocker les données
    nom_found = False
    prenom_found = False
    processed_data = []

    # Parcourt les phrases du texte
    for sentence in doc.sents:
        # Vérifie si "nom" est dans la phrase
        if "nom" in sentence.text.lower():
            nom_found = True

        # Vérifie si "prénom" est dans la phrase
        if "prénom" in sentence.text.lower():
            prenom_found = True

        # Ajoute la phrase au résultat si "nom" et "prénom" ont été trouvés
        if nom_found and prenom_found:
            processed_data.append(sentence.text)

    return processed_data

if __name__ == "__main__":
    # Exemple d'utilisation
    tesseract_output = "Votre texte extrait par Tesseract ici. Nom : Dupont Prénom : Jean. Autre texte..."
    result = process_tesseract_data(tesseract_output)
    print(result)

