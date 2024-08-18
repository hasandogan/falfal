const FortunesLogic = () => {
  const fortuneList = [
    {
      name: 'Tarot',
      description:
        'Tarot, kartlar aracılığıyla hayatına dair ipuçları ve rehberlik sunan eğlenceli bir keşif yoludur.',
      url: '/tarot',
      imageUrl: '/images/tarot.png',
    },
  ];
  return { fortuneList };
};

export default FortunesLogic;
