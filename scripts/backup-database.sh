#!/bin/bash
# ===================================
# SCRIPT DE BACKUP BASE DE DONNÉES - PGI AUTOMOBILE
# ===================================
#
# USAGE :
#   ./scripts/backup-database.sh
#
# CRON (backup quotidien à 3h du matin) :
#   0 3 * * * /path/to/PGI-Automobile/scripts/backup-database.sh
#
# ===================================

# Configuration
DB_NAME="pgi_automobile"
DB_USER="root"
DB_PASS=""
BACKUP_DIR="../backups"
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/backup_${DB_NAME}_${DATE}.sql"

# Créer le dossier de backup si nécessaire
mkdir -p $BACKUP_DIR

# Exécuter le backup
echo "🔄 Backup de la base de données $DB_NAME..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE

# Compresser le backup
echo "📦 Compression du backup..."
gzip $BACKUP_FILE

# Vérifier le succès
if [ $? -eq 0 ]; then
    echo "✅ Backup réussi : ${BACKUP_FILE}.gz"
    echo "📊 Taille : $(du -h ${BACKUP_FILE}.gz | cut -f1)"
else
    echo "❌ Erreur lors du backup !"
    exit 1
fi

# Nettoyer les backups de plus de 30 jours
echo "🧹 Nettoyage des anciens backups (> 30 jours)..."
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +30 -delete

echo "✅ Terminé !"
