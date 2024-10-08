import { WebView } from 'react-native-webview';
import Constants from 'expo-constants';
import {Platform, StyleSheet, View} from 'react-native';
import React from "react";
export default class App extends React.Component {
  render() {
    return Platform.OS === "web" ? (
        <iframe src="http://localhost:3000" height={'100%'} width={'100%'} />
    ) : (
        <View style={{ flex: 1 }}>
          <WebView
              source={{ uri: "http://localhost:3000" }}
              style={{marginTop: 22, flex: 1}}
          />
        </View>
    )
  }
}

