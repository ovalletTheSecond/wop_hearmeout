# Architecture et flux de l'application Scan Clean Food

## 📐 Architecture globale

```
┌─────────────────────────────────────────────────────────────┐
│                    SCAN CLEAN FOOD APP                       │
│                   Flutter Mobile Application                 │
└─────────────────────────────────────────────────────────────┘

┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│   Presentation   │───▶│   Business Logic │───▶│      Data        │
│      Layer       │◀───│      Layer       │◀───│     Layer        │
└──────────────────┘    └──────────────────┘    └──────────────────┘
        │                        │                        │
        │                        │                        │
    ┌───┴────┐              ┌────┴─────┐          ┌──────┴──────┐
    │ Pages  │              │ Providers│          │  Services   │
    │Widgets │              │  Models  │          │   Storage   │
    └────────┘              └──────────┘          └─────────────┘
```

## 🔄 Flux de données principal

### 1. Scan d'un produit

```
[User]
  │
  │ Tap "Scanner un produit"
  ↓
[HomePage]
  │
  │ Navigate
  ↓
[ScannerPage]
  │
  │ Start camera
  ↓
[MobileScanner]
  │
  │ Detect barcode
  ↓
[ScanProvider]
  │
  │ scanBarcode(code)
  ↓
[OpenFoodFactsService]
  │
  │ HTTP GET
  ↓
[Open Food Facts API]
  │
  │ Return product data
  ↓
[Product Model]
  │
  │ detectInsectIngredients()
  ↓
[ScanProvider]
  │
  │ Update state
  ├─────────────────┐
  │                 │
  │ Navigate        │ Add to history
  ↓                 ↓
[ResultPage]    [HistoryProvider]
  │                 │
  │ Display         │ Save to SharedPreferences
  ↓                 ↓
[User sees result] [History saved]
```

### 2. Consultation de l'historique

```
[User]
  │
  │ Tap history icon
  ↓
[HistoryPage]
  │
  │ Listen to provider
  ↓
[HistoryProvider]
  │
  │ Load from SharedPreferences
  ↓
[List<Product>]
  │
  │ Map to widgets
  ↓
[HistoryListItem] × N
  │
  │ Tap item
  ↓
[Product Details Modal]
  │
  │ Display full info
  ↓
[User views details]
```

## 🏗️ Architecture des composants

```
┌────────────────────────────────────────────────────────────────┐
│                          main.dart                              │
│  ┌──────────────────────────────────────────────────────┐      │
│  │              MultiProvider                           │      │
│  │  ┌────────────────┐    ┌────────────────┐          │      │
│  │  │ ScanProvider   │    │ HistoryProvider│          │      │
│  │  └────────────────┘    └────────────────┘          │      │
│  │                                                      │      │
│  │           MaterialApp                               │      │
│  │              │                                       │      │
│  │         HomePage                                    │      │
│  └──────────────────────────────────────────────────────┘      │
└────────────────────────────────────────────────────────────────┘
```

## 📦 Diagramme des dépendances

```
┌─────────────────────────────────────────────────────────────┐
│                    Pages & Widgets                           │
├─────────────────────────────────────────────────────────────┤
│  HomePage          ScannerPage      ResultPage    HistoryPage│
│     │                  │                │              │      │
│     └──────────────────┴────────────────┴──────────────┘      │
│                         │                                     │
│                    Providers                                  │
├─────────────────────────────────────────────────────────────┤
│              ScanProvider    HistoryProvider                 │
│                    │                │                         │
│                    └────────────────┘                         │
│                         │                                     │
│               Services & Models                               │
├─────────────────────────────────────────────────────────────┤
│         OpenFoodFactsService    Product Model                │
│                    │                │                         │
│                    └────────────────┘                         │
│                         │                                     │
│                   External                                    │
├─────────────────────────────────────────────────────────────┤
│    mobile_scanner  http  shared_preferences  provider        │
└─────────────────────────────────────────────────────────────┘
```

## 🔍 Détection d'insectes - Processus détaillé

```
[Product received from API]
          │
          │ Extract ingredients_text
          ↓
[String ingredientsText]
          │
          │ Convert to lowercase
          ↓
[Normalized text]
          │
          │ Loop through keywords
          ↓
┌─────────┴──────────┐
│  For each keyword  │
│  - insecte         │
│  - grillon         │
│  - cricket         │
│  - ...30+ terms    │
└─────────┬──────────┘
          │
          │ Check if contains keyword
          ↓
    ┌─────┴─────┐
    │  Found?   │
    └─────┬─────┘
          │
    ┏━━━━━┷━━━━━┓
    ┃    Yes    ┃    No
    ┗━━━━━┯━━━━━┛    │
          │          │ Continue
          │          ↓
          │     [Next keyword]
          │
          │ Add to detectedIngredients[]
          ↓
[List<String> detectedIngredients]
          │
          ↓
[Return to Product]
          │
          ↓
┌─────────┴──────────┐
│ hasInsectIngredients│
│   = list.isNotEmpty │
└─────────┬──────────┘
          │
          ↓
[Display result]
```

## 💾 Stockage des données

```
┌──────────────────────────────────────────────────────┐
│             SharedPreferences                         │
│  (Stockage local clé-valeur)                         │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Key: "scan_history"                                 │
│  Value: JSON String                                  │
│                                                       │
│  [                                                    │
│    {                                                  │
│      "barcode": "3228857000852",                     │
│      "name": "Pain de mie",                          │
│      "brands": "Marque X",                           │
│      "imageUrl": "https://...",                      │
│      "ingredientsText": "farine, eau...",            │
│      "detectedInsectIngredients": [],                │
│      "scanDate": "2024-10-30T12:00:00.000Z"         │
│    },                                                 │
│    ...                                                │
│  ]                                                    │
│                                                       │
│  Maximum: 50 produits                                │
│                                                       │
└──────────────────────────────────────────────────────┘
```

## 🎨 Hiérarchie des widgets

```
MaterialApp
  │
  └─ MultiProvider
      ├─ ScanProvider
      └─ HistoryProvider
          │
          └─ HomePage
              ├─ AppBar
              │   ├─ Title
              │   └─ History Icon
              ├─ Welcome Card
              ├─ Scan Button
              └─ Recent Scans List
                  └─ HistoryListItem × N

ScannerPage
  ├─ AppBar
  ├─ MobileScanner (Camera)
  ├─ Overlay (CustomPaint)
  ├─ Instructions (Bottom)
  └─ Flash Toggle (Top)

ResultPage
  ├─ AppBar
  └─ SingleChildScrollView
      ├─ Result Card
      │   ├─ Icon (✅ or ⚠️)
      │   ├─ Verdict Text
      │   └─ Detected Ingredients (if any)
      ├─ Product Info Card
      │   ├─ Image
      │   ├─ Name
      │   ├─ Brand
      │   └─ Ingredients
      └─ Action Buttons

HistoryPage
  ├─ AppBar
  │   └─ Clear History Button
  └─ ListView
      └─ Dismissible × N
          └─ HistoryListItem
              ├─ CircleAvatar (Icon)
              ├─ Product Name
              ├─ Brand
              ├─ Date
              └─ Status Icon
```

## 🔐 Gestion des permissions

```
[App Launch]
      │
      │ User taps "Scanner"
      ↓
[Check camera permission]
      │
  ┌───┴───┐
  │ Has?  │
  └───┬───┘
      │
┏━━━━━┷━━━━━┓
┃   Yes     ┃    No
┗━━━━━┯━━━━━┛    │
      │          │ Request permission
      │          ↓
      │    [Permission Dialog]
      │          │
      │      ┌───┴───┐
      │      │Granted│
      │      └───┬───┘
      │          │
      │    ┏━━━━━┷━━━━━┓
      │    ┃   Yes     ┃    No
      │    ┗━━━━━┯━━━━━┛    │
      │          │          │
      └──────────┴──────────┘
                 │
                 │ Show error
                 ↓
          [Open Camera]
```

## 📊 États de l'application

```
ScanProvider States:
  │
  ├─ Initial
  │   ├─ currentProduct: null
  │   ├─ isLoading: false
  │   └─ error: null
  │
  ├─ Loading
  │   ├─ currentProduct: null
  │   ├─ isLoading: true
  │   └─ error: null
  │
  ├─ Success
  │   ├─ currentProduct: Product
  │   ├─ isLoading: false
  │   └─ error: null
  │
  └─ Error
      ├─ currentProduct: null
      ├─ isLoading: false
      └─ error: String

HistoryProvider States:
  │
  ├─ Empty
  │   └─ history: []
  │
  ├─ Has Items
  │   └─ history: [Product, ...]
  │
  └─ After Operation
      └─ history: Updated list
```

## 🌐 Communication réseau

```
[ScanProvider]
      │
      │ scanBarcode(code)
      ↓
[OpenFoodFactsService]
      │
      │ getProduct(barcode)
      ↓
[HTTP Request]
      │
      │ GET https://world.openfoodfacts.org/api/v0/product/{barcode}.json
      ↓
[API Response]
      │
  ┌───┴───┐
  │Status │
  └───┬───┘
      │
┏━━━━━┷━━━━━┓
┃   200     ┃   Other
┗━━━━━┯━━━━━┛    │
      │          │ Error
      │          ↓
      │    [Return null]
      │
      │ Parse JSON
      ↓
[Extract data]
      │
      ├─ product_name
      ├─ brands
      ├─ image_url
      └─ ingredients_text
      │
      │ Detect insects
      ↓
[Create Product object]
      │
      ↓
[Return Product]
```

## 🎯 Points d'entrée utilisateur

```
1. Scan Button (Home) → Scanner Page
2. History Icon (AppBar) → History Page
3. "Voir tout" Button → History Page
4. History Item Tap → Product Details
5. Swipe Left → Delete Item
6. Clear All Button → Confirm Dialog → Clear History
7. Flash Button → Toggle Torch
8. Close Button (Result) → Home Page
```

---

Cette architecture garantit:
- ✅ Séparation des responsabilités
- ✅ Code maintenable et testable
- ✅ Flux de données unidirectionnel
- ✅ État centralisé avec Provider
- ✅ Services réutilisables
- ✅ UI découplée de la logique métier
