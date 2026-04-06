const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8080, host: '0.0.0.0' });

// Stockage des clients par restaurant
// { id_etablissement: Set<WebSocket> }
const etablissement = {};

wss.on('connection', ws => {
    console.log('✅ Client connected');

    ws.on('message', message => {
        let data;
        try {
            data = JSON.parse(message.toString());
        } catch (e) {
            console.log('❌  Invalid message');
            return;
        }

        // 🏷️ Enregistrement du client pour un restaurant
        if (data.type === 'register' && data.id_etablissement) {
            ws.id_etablissement = data.id_etablissement;

            if (!etablissement[data.id_etablissement]) {
                etablissement[data.id_etablissement] = new Set();
            }

            etablissement[data.id_etablissement].add(ws);
            console.log(`🏷️ Client register for restaurant ${data.id_etablissement}`);
            return;
        }

        // 📦 Nouvelle commande
        if (data.type === 'new_command' && data.id_etablissement) {
            const clientsRestaurant = etablissement[data.id_etablissement];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Command sent to restaurant ${data.id_etablissement}`);
            return;
        }

        // ✅ 🧾 TABLE TERMINÉE (AJOUT)
        if (data.type === 'table_completed' && data.id_etablissement) {
            const clientsRestaurant = etablissement[data.id_etablissement];
            if (!clientsRestaurant) return;

            clientsRestaurant.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify(data));
                }
            });

            console.log(`📤 Table ${data.table} completed sent to restaurant ${data.id_etablissement}`);
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
        console.log('⚠️ Client diconnected');
    });
});

console.log('🚀 WebSocket server started sur ws://0.0.0.0:8080');

