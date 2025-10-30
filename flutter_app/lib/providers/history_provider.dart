import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:scan_clean_food/models/product.dart';

class HistoryProvider with ChangeNotifier {
  List<Product> _history = [];
  static const String _historyKey = 'scan_history';

  List<Product> get history => _history;

  HistoryProvider() {
    _loadHistory();
  }

  /// Loads scan history from local storage
  Future<void> _loadHistory() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final historyJson = prefs.getString(_historyKey);
      
      if (historyJson != null) {
        final List<dynamic> decoded = json.decode(historyJson);
        _history = decoded.map((json) => Product.fromJson(json)).toList();
        notifyListeners();
      }
    } catch (e) {
      print('Error loading history: $e');
    }
  }

  /// Adds a product to the scan history
  Future<void> addToHistory(Product product) async {
    // Add to beginning of list
    _history.insert(0, product);
    
    // Keep only last 50 scans
    if (_history.length > 50) {
      _history = _history.sublist(0, 50);
    }
    
    await _saveHistory();
    notifyListeners();
  }

  /// Saves history to local storage
  Future<void> _saveHistory() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final historyJson = json.encode(_history.map((p) => p.toJson()).toList());
      await prefs.setString(_historyKey, historyJson);
    } catch (e) {
      print('Error saving history: $e');
    }
  }

  /// Clears all scan history
  Future<void> clearHistory() async {
    _history = [];
    await _saveHistory();
    notifyListeners();
  }

  /// Removes a specific product from history
  Future<void> removeFromHistory(int index) async {
    if (index >= 0 && index < _history.length) {
      _history.removeAt(index);
      await _saveHistory();
      notifyListeners();
    }
  }
}
