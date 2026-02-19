import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { useGame } from '../context/GameContext';
import PlayerCard from '../components/PlayerCard';
import PlayerMenu from '../components/PlayerMenu';
import EndGameModal from '../components/EndGameModal';

export default function GameScreen({ navigation }) {
  const { state, dispatch } = useGame();
  const [menuVisible, setMenuVisible] = useState(false);
  const [endGameVisible, setEndGameVisible] = useState(false);

  const players = state.players;

  const handleRestart = () => {
    dispatch({ type: 'RESET_GAME' });
  };

  const renderPlayers = () => {
    const count = players.length;

    if (count === 2) {
      return (
        <View style={styles.twoPlayerLayout}>
          <PlayerCard player={players[0]} isRotated={true} />
          <View style={styles.divider} />
          <PlayerCard player={players[1]} isRotated={false} />
        </View>
      );
    }

    if (count === 3) {
      return (
        <View style={styles.threePlayerLayout}>
          <View style={styles.topHalf}>
            <PlayerCard player={players[0]} isRotated={true} />
          </View>
          <View style={styles.divider} />
          <View style={styles.bottomHalf}>
            <PlayerCard player={players[1]} isRotated={false} />
            <View style={styles.verticalDivider} />
            <PlayerCard player={players[2]} isRotated={false} />
          </View>
        </View>
      );
    }

    // 4+ players: grid layout
    const rows = [];
    for (let i = 0; i < players.length; i += 2) {
      const pair = players.slice(i, i + 2);
      rows.push(
        <View key={i} style={styles.gridRow}>
          {pair.map((p, idx) => (
            <React.Fragment key={p.id}>
              {idx > 0 && <View style={styles.verticalDivider} />}
              <PlayerCard player={p} isRotated={false} />
            </React.Fragment>
          ))}
        </View>
      );
      if (i + 2 < players.length) {
        rows.push(<View key={`div-${i}`} style={styles.divider} />);
      }
    }

    return <View style={styles.gridLayout}>{rows}</View>;
  };

  return (
    <SafeAreaView style={styles.container} edges={['top', 'left', 'right']}>
      {/* Header */}
      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <TouchableOpacity
            style={styles.navBtn}
            onPress={() => navigation.navigate('History')}
          >
            <Text style={styles.navBtnText}>📜</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.navBtn}
            onPress={() => navigation.navigate('Stats')}
          >
            <Text style={styles.navBtnText}>📊</Text>
          </TouchableOpacity>
        </View>

        <Text style={styles.headerTitle}>⚔️ Life Counter</Text>

        <TouchableOpacity
          style={styles.menuBtn}
          onPress={() => setMenuVisible(true)}
        >
          <Text style={styles.menuBtnText}>☰</Text>
        </TouchableOpacity>
      </View>

      {/* Players area */}
      <View style={styles.playersArea}>{renderPlayers()}</View>

      {/* Bottom bar */}
      <View style={styles.bottomBar}>
        <TouchableOpacity
          style={styles.endBtn}
          onPress={() => setEndGameVisible(true)}
        >
          <Text style={styles.endBtnText}>⚑ Fin de partie</Text>
        </TouchableOpacity>
      </View>

      {/* Modals */}
      <PlayerMenu
        visible={menuVisible}
        onClose={() => setMenuVisible(false)}
      />
      <EndGameModal
        visible={endGameVisible}
        onClose={() => setEndGameVisible(false)}
        onRestart={handleRestart}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#1a1a2e',
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 12,
    paddingVertical: 8,
    backgroundColor: '#0f0f1a',
    borderBottomWidth: 1,
    borderBottomColor: '#0f3460',
    zIndex: 10,
  },
  headerLeft: {
    flexDirection: 'row',
    gap: 8,
  },
  headerTitle: {
    color: '#e94560',
    fontSize: 18,
    fontWeight: 'bold',
  },
  navBtn: {
    padding: 8,
    borderRadius: 8,
    backgroundColor: '#1a1a2e',
  },
  navBtnText: {
    fontSize: 18,
  },
  menuBtn: {
    padding: 8,
    borderRadius: 8,
    backgroundColor: '#1a1a2e',
    minWidth: 40,
    alignItems: 'center',
  },
  menuBtnText: {
    color: '#e94560',
    fontSize: 22,
    fontWeight: 'bold',
  },
  playersArea: {
    flex: 1,
  },
  twoPlayerLayout: {
    flex: 1,
    flexDirection: 'column',
  },
  threePlayerLayout: {
    flex: 1,
    flexDirection: 'column',
  },
  topHalf: {
    flex: 1,
    flexDirection: 'row',
  },
  bottomHalf: {
    flex: 1,
    flexDirection: 'row',
  },
  gridLayout: {
    flex: 1,
    flexDirection: 'column',
  },
  gridRow: {
    flex: 1,
    flexDirection: 'row',
  },
  divider: {
    height: 2,
    backgroundColor: '#0f3460',
  },
  verticalDivider: {
    width: 2,
    backgroundColor: '#0f3460',
  },
  bottomBar: {
    padding: 10,
    backgroundColor: '#0f0f1a',
    borderTopWidth: 1,
    borderTopColor: '#0f3460',
  },
  endBtn: {
    backgroundColor: '#e94560',
    borderRadius: 12,
    padding: 14,
    alignItems: 'center',
  },
  endBtnText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
