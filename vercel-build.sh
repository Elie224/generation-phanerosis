#!/bin/bash

echo "🚀 Build pour Vercel - Generation Phanerosis"

# Installation des dépendances
echo "📦 Installation des dépendances..."
npm ci --production

# Build des assets
echo "🔨 Build des assets..."
npm run build

# Vérification du dossier de sortie
echo "✅ Vérification du dossier de sortie..."
if [ -d "public/build" ]; then
    echo "✅ Dossier public/build trouvé"
    ls -la public/build/
else
    echo "❌ Dossier public/build manquant"
    exit 1
fi

echo "🎉 Build terminé avec succès !" 