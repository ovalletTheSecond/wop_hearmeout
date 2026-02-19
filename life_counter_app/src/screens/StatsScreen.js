import React, { useMemo } from 'react';
import {
  View,
  Text,
  FlatList,
  StyleSheet,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useGame } from '../context/GameContext';

function computeStats(history) {
  const statsMap = {};

  history.forEach((battle) => {
    battle.players.forEach((player) => {
      const key = player.name;
      if (!statsMap[key]) {
        statsMap[key] = { name: key, wins: 0, losses: 0, decks: {} };
      }
      if (player.id === battle.winnerId) {
        statsMap[key].wins += 1;
        const deck = battle.winnerDeck || player.deck || 'Inconnu';
        statsMap[key].decks[deck] = (statsMap[key].decks[deck] || 0) + 1;
      } else {
        statsMap[key].losses += 1;
      }
    });
  });

  return Object.values(statsMap).sort((a, b) => b.wins - a.wins);
}

function StatCard({ stat }) {
  const total = stat.wins + stat.losses;
  const winRate = total > 0 ? Math.round((stat.wins / total) * 100) : 0;
  const topDecks = Object.entries(stat.decks)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 3);

  return (
    <View style={styles.card}>
      <View style={styles.cardHeader}>
        <Text style={styles.playerName}>{stat.name}</Text>
        <Text style={styles.winRate}>{winRate}% victoires</Text>
      </View>

      <View style={styles.statsRow}>
        <View style={styles.statItem}>
          <Text style={styles.statNumber}>{stat.wins}</Text>
          <Text style={styles.statLabel}>Victoires</Text>
        </View>
        <View style={styles.statDivider} />
        <View style={styles.statItem}>
          <Text style={[styles.statNumber, styles.lossNumber]}>
            {stat.losses}
          </Text>
          <Text style={styles.statLabel}>Défaites</Text>
        </View>
        <View style={styles.statDivider} />
        <View style={styles.statItem}>
          <Text style={styles.statNumber}>{total}</Text>
          <Text style={styles.statLabel}>Parties</Text>
        </View>
      </View>

      {topDecks.length > 0 && (
        <View style={styles.decksSection}>
          <Text style={styles.decksTitle}>🃏 Decks gagnants</Text>
          {topDecks.map(([deck, count]) => (
            <View key={deck} style={styles.deckRow}>
              <Text style={styles.deckName}>{deck}</Text>
              <Text style={styles.deckCount}>{count}✓</Text>
            </View>
          ))}
        </View>
      )}
    </View>
  );
}

export default function StatsScreen() {
  const { state } = useGame();
  const stats = useMemo(() => computeStats(state.history), [state.history]);
  const plural = state.history.length > 1 ? 's' : '';

  return (
    <SafeAreaView style={styles.container} edges={['bottom']}>
      {stats.length === 0 ? (
        <View style={styles.emptyContainer}>
          <Text style={styles.emptyText}>📊</Text>
          <Text style={styles.emptyTitle}>Aucune statistique</Text>
          <Text style={styles.emptySubtitle}>
            Jouez des parties et utilisez "Fin de partie" pour voir les stats ici.
          </Text>
        </View>
      ) : (
        <FlatList
          data={stats}
          keyExtractor={(item) => item.name}
          renderItem={({ item }) => <StatCard stat={item} />}
          contentContainerStyle={styles.list}
          ListHeaderComponent={
            <Text style={styles.listHeader}>
              {state.history.length} combat{plural} enregistré{plural}
            </Text>
          }
        />
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#1a1a2e',
  },
  list: {
    padding: 16,
    gap: 12,
  },
  listHeader: {
    color: '#666',
    fontSize: 13,
    textAlign: 'center',
    marginBottom: 8,
  },
  card: {
    backgroundColor: '#16213e',
    borderRadius: 14,
    padding: 16,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  playerName: {
    color: '#e0e0e0',
    fontSize: 18,
    fontWeight: 'bold',
  },
  winRate: {
    color: '#5dbb85',
    fontSize: 14,
    fontWeight: '600',
  },
  statsRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    alignItems: 'center',
    backgroundColor: '#0f0f1a',
    borderRadius: 10,
    padding: 12,
    marginBottom: 12,
  },
  statItem: {
    alignItems: 'center',
    flex: 1,
  },
  statNumber: {
    color: '#e94560',
    fontSize: 28,
    fontWeight: 'bold',
  },
  lossNumber: {
    color: '#888',
  },
  statLabel: {
    color: '#666',
    fontSize: 12,
    marginTop: 2,
  },
  statDivider: {
    width: 1,
    height: 40,
    backgroundColor: '#0f3460',
  },
  decksSection: {
    gap: 4,
  },
  decksTitle: {
    color: '#a0a0c0',
    fontSize: 13,
    fontWeight: '600',
    marginBottom: 4,
  },
  deckRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 3,
  },
  deckName: {
    color: '#888',
    fontSize: 13,
  },
  deckCount: {
    color: '#5dbb85',
    fontSize: 13,
    fontWeight: '600',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 32,
  },
  emptyText: {
    fontSize: 56,
    marginBottom: 16,
  },
  emptyTitle: {
    color: '#e0e0e0',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 8,
    textAlign: 'center',
  },
  emptySubtitle: {
    color: '#666',
    fontSize: 14,
    textAlign: 'center',
    lineHeight: 22,
  },
});
