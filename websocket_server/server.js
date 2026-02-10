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
// { idunique: Set<WebSocket> }
const restaurants = {};

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
        if (data.type === 'register' && data.idunique) {
            ws.idunique = data.idunique;

            if (!restaurants[data.idunique]) {
                restaurants[data.idunique] = new Set();
            }

            restaurants[data.idunique].add(ws);
            console.log(`🏷️ Client enregistré pour restaurant ${data.idunique}`);
            return;
        }

        // 📦 Nouvelle commande
        if (data.type === 'nouvelle_commande' && data.idunique) {
            const clientsRestaurant = restaurants[data.idunique];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Commande envoyée au restaurant ${data.idunique}`);
            return;
        }

        // ✅ 🧾 TABLE TERMINÉE (AJOUT)
        if (data.type === 'table_terminee' && data.idunique) {
            const clientsRestaurant = restaurants[data.idunique];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Table ${data.table} terminée envoyée au restaurant ${data.idunique}`);
            return;
        }
    });

    ws.on('close', () => {
        if (ws.idunique && restaurants[ws.idunique]) {
            restaurants[ws.idunique].delete(ws);
            if (restaurants[ws.idunique].size === 0) {
                delete restaurants[ws.idunique];
            }
        }
        console.log('⚠️ Client déconnecté');
    });
});

console.log('🚀 WebSocket serveur démarré sur ws://0.0.0.0:8080');


console.log('WebSocket serveur démarré sur ws://0.0.0.0:8080');
