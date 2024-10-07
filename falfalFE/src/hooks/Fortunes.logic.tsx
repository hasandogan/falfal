const FortunesLogic = () => {
  const fortuneList = [
    {
      name: 'Tarot',
      description:
        'Tarot, kartlar aracılığıyla hayatına dair ipuçları ve rehberlik sunan eğlenceli bir keşif yoludur.',
      url: '/tarot',
      imageUrl: '/images/tarot.png',
    },
    {
      name: 'Kahve Falı',
      description: 'Kahve falı, kahve telvesi üzerindeki sembollerin yorumlanmasıyla geleceğe dair ipuçları veren bir fal çeşididir.',
      url: '/coffee',
      imageUrl: '/images/tarot.png',
    },
    {
      name: 'El Falı',
      description: 'Çok Yakında...',
      url: '/fortunes',
      imageUrl: '/images/tarot.png',
    },
    {
      name: 'Su Falı',
      description: 'Çok Yakında...',
      url: '/fortunes',
      imageUrl: '/images/tarot.png',
    },
  ];
  return { fortuneList };
};

export default FortunesLogic;
