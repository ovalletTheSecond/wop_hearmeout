# Changelog - Scan Clean Food

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-10-30

### Added
- 🎉 Initial release of Scan Clean Food
- ✨ Barcode scanner using device camera
- 🔍 Integration with Open Food Facts API
- 🐛 Detection of 30+ insect-based ingredient keywords (French & English)
- 📊 Product information display (name, brand, image, ingredients)
- ✅ Clear verdict display with color coding (green/red)
- 📚 Scan history with local storage (up to 50 items)
- 💾 Persistent data using SharedPreferences
- 🌙 Material Design 3 with light/dark theme support
- 🔦 Camera flash toggle for scanning in low light
- 🗑️ Swipe-to-delete in history
- 🚮 Clear all history option
- 🎨 Custom splash screen with light green background
- 📱 Full Android support (API 21+)
- 🔒 Camera permission handling
- 🌐 Internet connectivity handling
- 🇫🇷 French language interface

### Technical Details
- Flutter/Dart 3.5.0+
- mobile_scanner 5.2.3 for barcode scanning
- provider 6.1.2 for state management
- shared_preferences 2.3.2 for local storage
- http 1.2.2 for API calls
- Material Design 3 theming
- Package ID: com.scancleanfood.app
- Min SDK: 21, Target SDK: 34

### Keywords Detected
#### French
- insecte, grillon, poudre de grillon, farine de grillon
- tenebrio, vers de farine, ver de farine
- mouche soldat noire, protéine d'insecte, farine d'insecte
- alfitobius diaperinus, acheta domesticus, locusta migratoria
- criquet, larve, poudre d'insecte

#### English
- insect, cricket, cricket powder, cricket flour
- mealworm, black soldier fly, hermetia illucens
- insect protein, insect flour, lesser mealworm
- house cricket, locust, larva, larvae, insect powder

### Files Structure
```
flutter_app/
├── lib/
│   ├── main.dart
│   ├── models/product.dart
│   ├── services/open_food_facts_service.dart
│   ├── providers/ (scan_provider, history_provider)
│   ├── pages/ (home, scanner, result, history)
│   └── widgets/history_list_item.dart
├── android/ (full Android configuration)
├── README.md, USAGE.md, DEPLOYMENT.md, PROJECT.md
└── pubspec.yaml
```

### Known Limitations
- Requires internet connection to fetch product data
- Dependent on Open Food Facts database completeness
- Detection based on keyword matching in ingredient text
- Products not in Open Food Facts database won't be found

### Future Considerations
- [ ] Add support for more languages
- [ ] Offline mode with cached products
- [ ] Allow users to contribute missing products
- [ ] Add more insect species keywords
- [ ] Product favorites feature
- [ ] Export history to CSV
- [ ] Share results on social media
- [ ] Statistics dashboard
- [ ] iOS support

---

## Version Guidelines

### Version Format
- MAJOR.MINOR.PATCH+BUILD
- Example: 1.0.0+1

### Version Increment Rules
- **MAJOR**: Incompatible API changes or major redesign
- **MINOR**: Add functionality in a backwards compatible manner
- **PATCH**: Backwards compatible bug fixes
- **BUILD**: Build number (increments with each release)

### How to Release
1. Update version in `pubspec.yaml`
2. Update this CHANGELOG
3. Commit changes
4. Tag the release: `git tag v1.0.0`
5. Build release: `flutter build appbundle --release`
6. Upload to Play Store Console
7. Push tag: `git push --tags`

---

**Note**: This is the first release. Future versions will be documented here.
