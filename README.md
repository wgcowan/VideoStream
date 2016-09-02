Video Stream (plugin for Omeka)
===============================

[Video Stream] is a plugin for [Omeka] that embeds a [JW Player] video segment
player into item show pages. [JW Player] is a largely used open source video
player that can be used with any device. It supports a lots of protocol and can
be customized  with a lot of options for configuration.

See the original site that developed it for a [digital library project] of the
University of Indiana (USA). It uses the three available protocols of the
plugin:
- the standard "rtmp",
- the proprietary Adobe protocol "flash stream",
- the proprietary Apple protocol "HLS".

So it can be displayed on any device. This sample displays the description of
the whole item and a description for each segment of the video, that is updated
automatically when the video is playing. Each segment is a specific item, with
the same video.

A full [pdf documentation] is available on the site of the Indiana University
Libraries.


Installation
------------

Uncompress files and rename plugin folder "VideoStream".

Then install it like any other Omeka plugin and follow the config instructions.


Example
-------

To try it, set these options in the config page of the plugin:
- Display current: true
- Flash streaming: true
- HTTP streaming: true
- HLS streaming: true
- Display tuning : true

Other options can be set as wanted.

Then, create a new item and fill these elements with the metadata of the sample
above:
- Dublin Core
  - Title: "Intro / Slide: Summary"
  - Description: "Scientific computing has advanced due to many factors,..."
  - Creator: "Dr. Paul Messina"
  - Publisher: "Indiana University"
  - Date: "25 April 2013"
  - Rights: "Indiana University Board of Trustees"
  - Language: "English"
- Streaming Video
  - Video Filename: "radiotv/messina_20130425.mp4"
  - Video Streaming URL: "rtmp://flashstream.indiana.edu/ip/"
  - Video Type: "mp4:"
  - HLS Video Filename: "http://flashstream.indiana.edu/ip/radiotv/messina_20130425.mp4.m3u8"
  - HTTP Video Filename: "http://flashstream.indiana.edu/ip/radiotv/messina_20130425.mp4.m3u8"
  - Segment Start: "0:59:16"
  - Segment End: "1:01:22"

The tab "Segment Tuning" allows to set the segment manually (description and
position for start and end). To use it, the item should be saved a first time.
Normally, the video is already available in public view, but without segments.
So, re-edit the same item, go to this tab and go to position you want and click
"Set" for the segment start or the segment end. The first button allows to play
and to pause the stream. The description can be updated too. The values in this
tab are saved in place of the existing elements ("Dublin Core:Description", "Streaming Video:Segment Start"
and "Streaming Video:Segment End".

To add a segment to the same video, add a new item with the same values, except
the description, the segment start and the segment end. The item should be in
the same collection too. Furthermore, all the segments should have the value
"Scene" for the element "Segment Type". This can be modified directly in the
theme if wanted.

Elements for directories ("HLS Streaming Directory" and "HTTP Streaming Directory")
can be used in conjunction with the respective fields "Video Filename" to
simplify the management of urls, if wanted. The url for the "Flash" is built
from the "Video Streaming URL", the "Video Type" and the "Video Filename": here,
this is "rtmp://flashstream.indiana.edu/ip/mp4:radiotv/messina_20130425.mp4".


Warning
-------

Use it at your own risk.

It's always recommended to backup your files and database regularly so you can
roll back if needed.


Troubleshooting
---------------

See online issues on the [plugin issues] page on GitHub.


Licenses
--------

* This plugin is published under [GNU/GPL].

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

* JW Player library (see full [JW Player license] online)

The use of this library is governed by a Creative Commons license. You can use,
modify, copy, and distribute this edition as long as it’s for non-commercial
use, you provide attribution, and share under a similar license.


Contact
-------

Current maintainers:

* William Cowan (see [wgcowan])
* Daniel Berthereau (see [Daniel-KM])


Copyright
---------

* Copyright William Cowan, 2012-2016
* Copyright Daniel Berthereau, 2015-2016


[Video Stream]: https://github.com/wgcowan/VideoStream
[Omeka]: https://omeka.org
[JW Player]: https://www.jwplayer.com/
[digital library project]: http://www.dlib.indiana.edu/projects/omeka2/items/show/3558
[pdf documentation]: http://www.dlib.indiana.edu/projects/omeka2/items/show/3582
[plugin issues]: https://github.com/wgcowan/VideoStream/issues
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[JW Player license]: http://www.jwplayer.com/license
[wgcowan]: https://github.com/wgcowan "William Cowan"
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
