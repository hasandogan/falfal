import { FortuneTypeEnum } from '@/utils/enums/FortuneTypeEnum';

export interface IFortune {
  id: number;
  date: string;
  type: FortuneTypeEnum;
  message: string;
  question: string;
}
