import 'package:flutter/material.dart';
import 'package:scan_clean_food/models/product.dart';
import 'package:intl/intl.dart';

class HistoryListItem extends StatelessWidget {
  final Product product;
  final VoidCallback? onTap;

  const HistoryListItem({
    super.key,
    required this.product,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final dateFormat = DateFormat('dd/MM/yyyy HH:mm');
    
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 4, horizontal: 8),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        leading: CircleAvatar(
          backgroundColor: product.hasInsectIngredients 
              ? Colors.red.shade100 
              : Colors.green.shade100,
          child: Icon(
            product.hasInsectIngredients ? Icons.warning : Icons.check_circle,
            color: product.hasInsectIngredients ? Colors.red : Colors.green,
          ),
        ),
        title: Text(
          product.name,
          style: const TextStyle(fontWeight: FontWeight.bold),
          maxLines: 2,
          overflow: TextOverflow.ellipsis,
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 4),
            Text(product.brands),
            const SizedBox(height: 4),
            Text(
              dateFormat.format(product.scanDate),
              style: TextStyle(
                fontSize: 12,
                color: Theme.of(context).colorScheme.outline,
              ),
            ),
          ],
        ),
        trailing: Icon(
          product.hasInsectIngredients ? Icons.bug_report : Icons.verified,
          color: product.hasInsectIngredients ? Colors.red : Colors.green,
        ),
        onTap: onTap,
      ),
    );
  }
}
