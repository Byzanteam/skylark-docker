#!/bin/bash
mysqldump -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE > /home/backup/$MYSQL_DATABASE_$(date +%Y%m%d_%H%M%S).sql