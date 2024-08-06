<template>
  <div>
    <sirv-media-viewer
      :id="id"
      :slides="slides"
    ></sirv-media-viewer>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { loadScript } from '@sirv/sirvjs-vue';

const viewer = ref(null);
const id = ref('smv-test');
const prev = () => {
  if (viewer.value) {
    viewer.value.prev();
  }
};

const next = () => {
  if (viewer.value) {
    viewer.value.next();
  }
};

onMounted(() => {
  loadScript().then(sirv => {
    sirv.start();
    sirv.on('viewer:ready', e => {
      if (e.id === 'smv-test') {
        viewer.value = e;
        console.log('viewer:ready', e);
      }
    });
  });
});
  const slides = [
    {
      src: 'https://demo.sirv.com/demo/Switch/switch-front.jpg',
      type: 'zoom'
    },
    {
      src: 'https://demo.sirv.com/Examples/Coach/Coach.spin',
      type: 'spin'
    },
    {
      src: 'https://demo.sirv.com/demo/Switch/nintendo_switch.glb',
      type: 'model'
    },
    {
      src: 'https://demo.sirv.com/demo/Switch/switch.mp4',
      type: 'video'
    }
  ];
</script>
