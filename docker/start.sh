#!/bin/bash
echo "Starting services..."
/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf 