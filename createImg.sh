export IMAGE_DIR=image_file_avhdd_v2_101220

rm -Rf $IMAGE_DIR
tar -xvf image_file_avhdd_v2_101220.tar.bz2

cd patches
./patches.sh

cd ~/HMR/$IMAGE_DIR/components/tmp/rootfs/usr/local/bin
rm -Rf *
cd ../../..
tar -cjvf ../../packages/package2/root.nand.tar.bz2 *
cd ~/HMR/$IMAGE_DIR
./rc.front vfd
./rc.guide hmr
make image install_ap=1 PACKAGES=package2
