for f in *.png
do convert $f -resize 64x74 $f@2x.png
done
