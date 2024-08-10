import { FortuneTypeEnum } from '../enums/FortuneTypeEnum';

export const getTitleByFortuneType = (type: FortuneTypeEnum) => {
  switch (type) {
    case FortuneTypeEnum.tarot:
      return 'Tarot';
    default:
      break;
  }
};
