# Flutter App Addition - Scan Clean Food

## 📱 Nouvelle Application Mobile

Ce dépôt contient maintenant une **application mobile Flutter complète** en plus du projet Laravel existant.

## 🆕 Ce qui a été ajouté

### Application Flutter : Scan Clean Food
Une application Android native pour détecter les ingrédients d'origine insecte dans les produits alimentaires.

**Emplacement** : `/flutter_app/`

### Fonctionnalités principales
- 🔍 Scanner de code-barres via la caméra
- 📊 Intégration API Open Food Facts
- 🐛 Détection automatique d'ingrédients d'insectes (30+ mots-clés)
- ✅ Affichage de verdict coloré (vert/rouge)
- 📚 Historique local des scans
- 🌙 Interface Material Design 3 (thème clair/sombre)

## 📂 Structure du dépôt

```
wop_hearmeout/
├── 🌐 Laravel Web App (existant)
│   ├── app/
│   ├── resources/
│   ├── routes/
│   ├── database/
│   ├── composer.json
│   └── README.md
│
└── 📱 Flutter Mobile App (nouveau)
    └── flutter_app/
        ├── lib/                    # Code Dart
        ├── android/                # Configuration Android
        ├── pubspec.yaml            # Dépendances
        ├── README.md               # Documentation
        ├── USAGE.md                # Guide utilisateur
        ├── DEPLOYMENT.md           # Guide déploiement
        ├── PROJECT.md              # Doc technique
        └── COMPLETION_SUMMARY.md   # Résumé complet
```

## 🔧 Comment utiliser

### Application Laravel (existante)
```bash
# Voir README.md à la racine
composer install
php artisan serve
```

### Application Flutter (nouvelle)
```bash
cd flutter_app
flutter pub get
flutter run
```

## 📖 Documentation

### Pour l'application Flutter
Consultez ces fichiers dans `/flutter_app/` :
- **README.md** : Vue d'ensemble et installation
- **USAGE.md** : Guide utilisateur complet
- **DEPLOYMENT.md** : Comment déployer sur Play Store
- **PROJECT.md** : Documentation technique
- **COMPLETION_SUMMARY.md** : Résumé détaillé

### Pour l'application Laravel
Consultez le **README.md** à la racine du dépôt.

## 🎯 Objectifs atteints

L'application Flutter répond à tous les critères du cahier des charges :
- ✅ Framework Flutter avec Dart
- ✅ Scanner mobile_scanner
- ✅ API Open Food Facts
- ✅ Détection d'ingrédients d'insectes
- ✅ Gestion d'état avec Provider
- ✅ Stockage local avec SharedPreferences
- ✅ Material Design 3
- ✅ Package com.scancleanfood.app
- ✅ Android 13+ (min SDK 21)
- ✅ Prêt pour le Play Store

## 🚀 Statut

- **Laravel App** : ✅ Fonctionnel (existant)
- **Flutter App** : ✅ **PRÊT POUR PRODUCTION**

## 📝 Notes importantes

1. Les deux applications sont **indépendantes**
2. Elles peuvent coexister dans le même dépôt
3. Chacune a sa propre documentation
4. Chacune a son propre processus de déploiement

## 🤝 Contribution

Pour contribuer :
- **Laravel** : Suivez les instructions dans README.md racine
- **Flutter** : Consultez flutter_app/README.md

## 📞 Support

- Laravel : Issues GitHub pour le projet web
- Flutter : Issues GitHub pour l'app mobile

---

**Dernière mise à jour** : Octobre 2025  
**Status** : ✅ Deux applications complètes et fonctionnelles
