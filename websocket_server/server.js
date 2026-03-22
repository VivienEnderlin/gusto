// ws-server.js
const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080, host: '0.0.0.0' });

wss.on('connection', ws => {
    console.log('✅ Client connecté');

    ws.on('message', message => {

        console.log('📥 Message reçu par le serveur :', message.toString());
        // On rediffuse le message à tous les clients
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    ws.on('close', () => console.log('⚠️ Client déconnecté'));
});// ws-server.js
const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8080, host: '0.0.0.0' });

// Stockage des clients par restaurant
// { id_etablissement: Set<WebSocket> }
const etablissement = {};

wss.on('connection', ws => {
    console.log('✅ Client connecté');

    ws.on('message', message => {
        let data;
        try {
            data = JSON.parse(message.toString());
        } catch (e) {
            console.log('❌ Message invalide');
            return;
        }

        // 🏷️ Enregistrement du client pour un restaurant
        if (data.type === 'register' && data.id_etablissement) {
            ws.id_etablissement = data.id_etablissement;

            if (!etablissement[data.id_etablissement]) {
                etablissement[data.id_etablissement] = new Set();
            }

            etablissement[data.id_etablissement].add(ws);
            console.log(`🏷️ Client enregistré pour restaurant ${data.id_etablissement}`);
            return;
        }

        // 📦 Nouvelle commande
        if (data.type === 'nouvelle_commande' && data.id_etablissement) {
            const clientsRestaurant = etablissement[data.id_etablissement];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Commande envoyée au restaurant ${data.id_etablissement}`);
            return;
        }

        // ✅ 🧾 TABLE TERMINÉE (AJOUT)
        if (data.type === 'table_terminee' && data.id_etablissement) {
            const clientsRestaurant = etablissement[data.id_etablissement];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Table ${data.table} terminée envoyée au restaurant ${data.id_etablissement}`);
            return;
        }
    });

    ws.on('close', () => {
        if (ws.id_etablissement && etablissement[ws.id_etablissement]) {
            etablissement[ws.id_etablissement].delete(ws);
            if (etablissement[ws.id_etablissement].size === 0) {
                delete etablissement[ws.id_etablissement];
            }
        }
        console.log('⚠️ Client déconnecté');
    });
});

console.log('🚀 WebSocket serveur démarré sur ws://0.0.0.0:8080');


console.log('WebSocket serveur démarré sur ws://0.0.0.0:8080');
