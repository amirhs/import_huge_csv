#!/bin/bash

# Check if cron is running
if ! ps aux | grep -q "[c]ron"; then
    echo "Cron is not running"
    exit 1
fi

# Check if PHP server is running
if ! ps aux | grep -q "[p]hp -S"; then
    echo "PHP server is not running"
    exit 1
fi

exit 0 