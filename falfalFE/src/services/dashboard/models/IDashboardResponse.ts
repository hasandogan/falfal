import { IFortune } from './IFortune';

export interface IDashboardResponse {
  pendingProcessExpireDate: string;
  createdAt: string;
  fortunes: IFortune[];
}
