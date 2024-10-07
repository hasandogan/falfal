import { FortuneTypeEnum } from '@/utils/enums/FortuneTypeEnum';

export interface IFortune {
  id: number;
  page: string;
  date: string;
  type: FortuneTypeEnum;
  message: string;
  question: string;
}
