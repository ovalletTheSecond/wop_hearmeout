# Scan Clean Food - Flutter Mobile Application

## 📱 À propos du projet

**Scan Clean Food** est une application mobile Android native développée avec Flutter qui permet aux utilisateurs de détecter les ingrédients d'origine insecte dans les produits alimentaires en scannant simplement leur code-barres.

L'application interroge l'API publique d'Open Food Facts pour obtenir les informations sur les produits, puis analyse automatiquement la liste des ingrédients pour détecter la présence de mots-clés liés aux insectes.

## 🎯 Fonctionnalités

### Principales
- ✅ **Scanner de code-barres** via la caméra du téléphone
- ✅ **Détection automatique** des ingrédients d'origine insecte
- ✅ **Résultats clairs** avec verdict coloré (vert/rouge)
- ✅ **Historique local** des scans avec stockage persistant
- ✅ **Interface intuitive** Material Design 3
- ✅ **Thème adaptatif** clair/sombre selon les préférences système

### Détails techniques
- 🔍 Détection de plus de 30 mots-clés en français et anglais
- 📊 Affichage des informations produit (nom, marque, image, ingrédients)
- 💾 Sauvegarde automatique de l'historique (jusqu'à 50 scans)
- 🔦 Support du flash de la caméra pour scanner dans le noir
- 🗑️ Gestion de l'historique (suppression par glissement, effacement total)

## 🏗️ Architecture

### Structure du projet
```
flutter_app/
├── lib/
│   ├── main.dart                          # Point d'entrée de l'application
│   ├── models/
│   │   └── product.dart                   # Modèle de données Product
│   ├── services/
│   │   └── open_food_facts_service.dart   # Service API Open Food Facts
│   ├── providers/
│   │   ├── scan_provider.dart             # Gestion d'état des scans
│   │   └── history_provider.dart          # Gestion d'état de l'historique
│   ├── pages/
│   │   ├── home_page.dart                 # Page d'accueil
│   │   ├── scanner_page.dart              # Page de scan
│   │   ├── result_page.dart               # Page de résultat
│   │   └── history_page.dart              # Page d'historique
│   └── widgets/
│       └── history_list_item.dart         # Widget pour items d'historique
├── android/                                # Configuration Android
├── pubspec.yaml                            # Dépendances Flutter
├── README.md                               # Documentation principale
├── USAGE.md                                # Guide utilisateur
└── DEPLOYMENT.md                           # Guide de déploiement
```

### Stack technique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Framework | Flutter/Dart | 3.5.0+ |
| Scanner | mobile_scanner | 5.2.3 |
| État | provider | 6.1.2 |
| HTTP | http | 1.2.2 |
| Stockage | shared_preferences | 2.3.2 |
| Permissions | permission_handler | 11.3.1 |
| Dates | intl | 0.19.0 |
| UI | Material Design 3 | - |

### Flux de données

```
User
  ↓ (scanne)
ScannerPage → MobileScanner
  ↓ (code-barres détecté)
ScanProvider → OpenFoodFactsService → API Open Food Facts
  ↓ (produit récupéré)
Product Model → Détection d'insectes
  ↓ (résultat)
ResultPage (affichage) + HistoryProvider (sauvegarde)
  ↓
HistoryPage (consultation)
```

## 🔧 Installation et développement

### Prérequis
- Flutter SDK 3.5.0 ou supérieur
- Android Studio ou VS Code avec extensions Flutter
- Android SDK (API 21 minimum, cible API 34)

### Installation
```bash
# Cloner le repository
git clone https://github.com/ovalletTheSecond/wop_hearmeout.git
cd wop_hearmeout/flutter_app

# Installer les dépendances
flutter pub get

# Lancer l'application (avec un appareil connecté ou émulateur)
flutter run
```

### Commandes utiles
```bash
# Analyse du code
flutter analyze

# Formater le code
flutter format .

# Compiler pour release
flutter build apk --release
flutter build appbundle --release

# Tests
flutter test
```

## 🌐 API et données

### Open Food Facts API
L'application utilise l'API REST d'Open Food Facts :
- **Endpoint** : `https://world.openfoodfacts.org/api/v0/product/{barcode}.json`
- **Méthode** : GET
- **Authentification** : Aucune
- **Limite** : Aucune connue
- **Documentation** : https://wiki.openfoodfacts.org/API

### Mots-clés détectés
L'application recherche ces termes dans les ingrédients :

**Français :**
- insecte, grillon, poudre de grillon, farine de grillon
- tenebrio, vers de farine, ver de farine
- mouche soldat noire, protéine d'insecte, farine d'insecte
- alfitobius diaperinus, acheta domesticus, locusta migratoria
- criquet, larve, poudre d'insecte

**Anglais :**
- insect, cricket, cricket powder, cricket flour
- mealworm, black soldier fly, hermetia illucens
- insect protein, insect flour, lesser mealworm
- house cricket, locust, larva, larvae, insect powder

## 📱 Configuration Android

### Package
- **ID** : `com.scancleanfood.app`
- **Nom** : Scan Clean Food

### Permissions
```xml
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.INTERNET" />
```

### Versions
- **Min SDK** : 21 (Android 5.0 Lollipop)
- **Target SDK** : 34 (Android 14)
- **Compile SDK** : 34

### Thème
- **Couleur primaire** : #8BC34A (Light Green)
- **Design** : Material Design 3
- **Modes** : Clair et Sombre

## 🚀 Déploiement

Pour déployer sur le Google Play Store :
1. Consultez le guide détaillé dans `DEPLOYMENT.md`
2. Générez une clé de signature
3. Compilez un AAB signé
4. Préparez les assets du Play Store
5. Soumettez pour révision

## 📝 Tests

### Tests manuels recommandés
- [ ] Scanner différents types de codes-barres (EAN-13, EAN-8, UPC-A)
- [ ] Tester avec des produits contenant des insectes
- [ ] Tester avec des produits sans insectes
- [ ] Vérifier le stockage de l'historique
- [ ] Tester la rotation d'écran
- [ ] Vérifier les thèmes clair/sombre
- [ ] Tester sans connexion Internet (échec gracieux)
- [ ] Vérifier les permissions caméra

### Produits de test (codes-barres)
Pour tester l'application, vous pouvez utiliser ces codes-barres :
- `3228857000852` - Pain de mie (sans insectes)
- `3017620422003` - Nutella (sans insectes)
- `5449000000996` - Coca-Cola (sans insectes)

Note : Pour des produits avec insectes, vous devrez en trouver en magasin ou contribuer à Open Food Facts.

## 🤝 Contribution

### Comment contribuer
1. Fork le projet
2. Créez une branche pour votre fonctionnalité
3. Committez vos changements
4. Poussez vers la branche
5. Ouvrez une Pull Request

### Standards de code
- Suivre les conventions Dart/Flutter
- Utiliser `flutter format` avant de commiter
- Passer `flutter analyze` sans erreurs
- Commenter le code complexe
- Mettre à jour la documentation si nécessaire

## 📄 Licence

MIT License

Copyright (c) 2025 Scan Clean Food

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## 👥 Auteurs

Développé pour le projet wop_hearmeout

## 🔗 Liens utiles

- [Repository GitHub](https://github.com/ovalletTheSecond/wop_hearmeout)
- [Open Food Facts](https://world.openfoodfacts.org/)
- [Flutter Documentation](https://docs.flutter.dev/)
- [Material Design 3](https://m3.material.io/)

## 📞 Support

Pour toute question ou problème :
- Ouvrez une issue sur GitHub
- Consultez la documentation dans `USAGE.md`
- Vérifiez les FAQ dans le guide utilisateur

---

**Version actuelle** : 1.0.0  
**Dernière mise à jour** : Octobre 2025  
**Statut** : ✅ Prêt pour production
