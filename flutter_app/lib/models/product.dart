class Product {
  final String barcode;
  final String name;
  final String brands;
  final String? imageUrl;
  final String ingredientsText;
  final List<String> detectedInsectIngredients;
  final DateTime scanDate;

  Product({
    required this.barcode,
    required this.name,
    required this.brands,
    this.imageUrl,
    required this.ingredientsText,
    required this.detectedInsectIngredients,
    required this.scanDate,
  });

  bool get hasInsectIngredients => detectedInsectIngredients.isNotEmpty;

  Map<String, dynamic> toJson() {
    return {
      'barcode': barcode,
      'name': name,
      'brands': brands,
      'imageUrl': imageUrl,
      'ingredientsText': ingredientsText,
      'detectedInsectIngredients': detectedInsectIngredients,
      'scanDate': scanDate.toIso8601String(),
    };
  }

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      barcode: json['barcode'] ?? '',
      name: json['name'] ?? '',
      brands: json['brands'] ?? '',
      imageUrl: json['imageUrl'],
      ingredientsText: json['ingredientsText'] ?? '',
      detectedInsectIngredients: List<String>.from(json['detectedInsectIngredients'] ?? []),
      scanDate: DateTime.parse(json['scanDate']),
    );
  }
}
