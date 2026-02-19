import React, { useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  TextInput,
  StyleSheet,
  Modal,
  ScrollView,
} from 'react-native';
import { useGame } from '../context/GameContext';

const PRESETS = [-7, -5, -3, -2, -1, 1, 2, 3, 5, 7];

export default function PlayerCard({ player, isRotated }) {
  const { dispatch } = useGame();
  const [editModal, setEditModal] = useState(false);
  const [tempName, setTempName] = useState(player.name);
  const [tempDeck, setTempDeck] = useState(player.deck);

  const isDead = player.hp <= 0;

  const changeHp = (delta) => {
    dispatch({ type: 'CHANGE_HP', playerId: player.id, delta });
  };

  const saveEdit = () => {
    dispatch({
      type: 'UPDATE_PLAYER',
      playerId: player.id,
      updates: { name: tempName || player.name, deck: tempDeck },
    });
    setEditModal(false);
  };

  const openEdit = () => {
    setTempName(player.name);
    setTempDeck(player.deck);
    setEditModal(true);
  };

  const containerStyle = [
    styles.container,
    isDead && styles.containerDead,
    isRotated && styles.rotated,
  ];

  return (
    <View style={containerStyle}>
      {/* Player info tap to edit */}
      <TouchableOpacity onPress={openEdit} style={styles.playerInfo}>
        <Text style={[styles.playerName, isDead && styles.textDead]}>
          {player.name}
        </Text>
        {player.deck ? (
          <Text style={[styles.deckName, isDead && styles.textDead]}>
            🃏 {player.deck}
          </Text>
        ) : (
          <Text style={styles.deckPlaceholder}>Appuyer pour modifier</Text>
        )}
      </TouchableOpacity>

      {/* HP Display */}
      <View style={styles.hpRow}>
        <TouchableOpacity
          style={[styles.hpBtn, styles.btnMinus]}
          onPress={() => changeHp(-1)}
        >
          <Text style={styles.hpBtnText}>−</Text>
        </TouchableOpacity>

        <View style={styles.hpContainer}>
          <Text style={[styles.hpText, isDead && styles.hpTextDead]}>
            {player.hp}
          </Text>
          {isDead && <Text style={styles.deadLabel}>☠ MORT</Text>}
        </View>

        <TouchableOpacity
          style={[styles.hpBtn, styles.btnPlus]}
          onPress={() => changeHp(1)}
        >
          <Text style={styles.hpBtnText}>+</Text>
        </TouchableOpacity>
      </View>

      {/* Quick presets */}
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        style={styles.presetsScroll}
        contentContainerStyle={styles.presetsContainer}
      >
        {PRESETS.map((delta) => (
          <TouchableOpacity
            key={delta}
            style={[
              styles.presetBtn,
              delta < 0 ? styles.presetMinus : styles.presetPlus,
            ]}
            onPress={() => changeHp(delta)}
          >
            <Text style={styles.presetText}>
              {delta > 0 ? `+${delta}` : delta}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Edit modal */}
      <Modal
        visible={editModal}
        transparent
        animationType="slide"
        onRequestClose={() => setEditModal(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Modifier le joueur</Text>

            <Text style={styles.inputLabel}>Pseudo</Text>
            <TextInput
              style={styles.input}
              value={tempName}
              onChangeText={setTempName}
              placeholder="Ex: DarkWizard"
              placeholderTextColor="#666"
            />

            <Text style={styles.inputLabel}>Deck</Text>
            <TextInput
              style={styles.input}
              value={tempDeck}
              onChangeText={setTempDeck}
              placeholder="Ex: Dragons Bleus"
              placeholderTextColor="#666"
            />

            <View style={styles.modalButtons}>
              <TouchableOpacity
                style={[styles.modalBtn, styles.modalBtnCancel]}
                onPress={() => setEditModal(false)}
              >
                <Text style={styles.modalBtnText}>Annuler</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.modalBtn, styles.modalBtnSave]}
                onPress={saveEdit}
              >
                <Text style={styles.modalBtnText}>Enregistrer</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#16213e',
    borderWidth: 1,
    borderColor: '#0f3460',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 12,
    gap: 8,
  },
  containerDead: {
    backgroundColor: '#2d0a0a',
    borderColor: '#8b0000',
  },
  rotated: {
    transform: [{ rotate: '180deg' }],
  },
  playerInfo: {
    alignItems: 'center',
  },
  playerName: {
    color: '#e0e0e0',
    fontSize: 18,
    fontWeight: 'bold',
  },
  deckName: {
    color: '#a0a0c0',
    fontSize: 13,
    marginTop: 2,
  },
  deckPlaceholder: {
    color: '#555',
    fontSize: 12,
    marginTop: 2,
    fontStyle: 'italic',
  },
  textDead: {
    color: '#ff6666',
  },
  hpRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 16,
  },
  hpBtn: {
    width: 56,
    height: 56,
    borderRadius: 28,
    justifyContent: 'center',
    alignItems: 'center',
  },
  btnMinus: {
    backgroundColor: '#8b0000',
  },
  btnPlus: {
    backgroundColor: '#1a6b2e',
  },
  hpBtnText: {
    color: '#fff',
    fontSize: 30,
    fontWeight: 'bold',
    lineHeight: 36,
  },
  hpContainer: {
    alignItems: 'center',
    minWidth: 80,
  },
  hpText: {
    color: '#e94560',
    fontSize: 64,
    fontWeight: 'bold',
    lineHeight: 72,
  },
  hpTextDead: {
    color: '#ff0000',
  },
  deadLabel: {
    color: '#ff0000',
    fontSize: 14,
    fontWeight: 'bold',
    marginTop: 2,
  },
  presetsScroll: {
    maxHeight: 40,
  },
  presetsContainer: {
    flexDirection: 'row',
    gap: 6,
    paddingHorizontal: 4,
  },
  presetBtn: {
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    minWidth: 36,
    alignItems: 'center',
  },
  presetMinus: {
    backgroundColor: '#5c1111',
  },
  presetPlus: {
    backgroundColor: '#0d4a1e',
  },
  presetText: {
    color: '#fff',
    fontSize: 13,
    fontWeight: '600',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.7)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  modalContent: {
    backgroundColor: '#1a1a2e',
    borderRadius: 16,
    padding: 24,
    width: '100%',
    maxWidth: 400,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  modalTitle: {
    color: '#e94560',
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  inputLabel: {
    color: '#a0a0c0',
    fontSize: 14,
    marginBottom: 4,
    marginTop: 8,
  },
  input: {
    backgroundColor: '#0f0f1a',
    color: '#e0e0e0',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    borderWidth: 1,
    borderColor: '#0f3460',
  },
  modalButtons: {
    flexDirection: 'row',
    gap: 12,
    marginTop: 20,
  },
  modalBtn: {
    flex: 1,
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
  },
  modalBtnCancel: {
    backgroundColor: '#333',
  },
  modalBtnSave: {
    backgroundColor: '#e94560',
  },
  modalBtnText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: 'bold',
  },
});
