#!/bin/sh
#
# Patch to recover 557 Wifi driver for 8187
local NET_DRIVERS=../../$IMAGE_DIR/components/tmp/rootfs/lib/modules/2.6.12.6-VENUS/kernel/drivers/net/wireless/realtek/rtl8187

#mv $NET_DRIVERS/rtl8187/r8187.ko $NET_DRIVERS/rtl8187/r8187.ko.bak 
cp r8187.ko $NET_DRIVERS/rtl8187/r8187.ko 
#mv $NET_DRIVERS/ieee80211/ieee80211-8187.ko $NET_DRIVERS/ieee80211/ieee80211-8187.ko.bak
cp ieee80211-8187.ko $NET_DRIVERS/ieee80211/ieee80211-8187.ko
