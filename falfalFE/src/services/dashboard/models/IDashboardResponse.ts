import { IFortune } from './IFortune';

export interface IDashboardResponse {
  pendingProcessExpireDate: string;
  fortunes: IFortune[];
}
