#!/bin/bash
# My first script

#!/bin/bash
# in image outimage bg image My first script


convert $1 -background none -gravity south -splice 0x50 \
          \( +clone -sparse-color barycentric '-50,0 black 290,0 white' \) \
          \( +clone -function arcsin 0.4 \) \
          \( -clone 1 -level 25%,75% \
                 -function polynomial -4,4,0 -gamma 2 \
                 +level 50%,0 \) \
          -delete 1 -swap 0,1  miff:- |\
     composite - -virtual-pixel transparent  -displace 75x45  $2;


#convert $3 $2 -geometry -29+15 -composite $2
convert $3 $2 -geometry -29+15 -composite $2; 

