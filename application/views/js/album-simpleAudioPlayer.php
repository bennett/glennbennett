<script src="/jsplayer/js/jquery.simpleaudioplayer.js"></script>

<script>
$('#demo').simpleAudioPlayer({
  title: "<?php echo $sub_title; ?>",
  chapters: [
    {
      "seconds": 30,
      "title": "<strong>Chapter 1:</strong> Text Here"
    },
    {
      "seconds": 60,
      "title": "<strong>Chapter 2:</strong> Text Here"
    },
    {
      "seconds": 90,
      "title": "<strong>Chapter 3:</strong> Text Here"
    },
  ]
});
</script>