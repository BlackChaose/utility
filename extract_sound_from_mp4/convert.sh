#! /bin/bash
#$tmp = `ffmpeg -i ./video/extreme_ways.mp4 -acodec libmp3lame -metadata TITLE="Extreme ways" extreme_ways.mp3`
echo "hello!"
path_to_src="./video/"
path_to_dest="./sound/"
res=$(ls $path_to_src)
for n in $res
do
	fn="${n%%.mp4}"
	`ffmpeg -i ${path_to_src}${n} -acodec libmp3lame -metadata TITLE="${fn}" ${path_to_dest}${fn}.mp3`
done
echo "end"
