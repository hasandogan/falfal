import { IFortune } from './IFortune';

export interface IDashboardResponse {
  pendingProcess: IPendingProcess;
  createdAt: string;
  fortunes: IFortune[];
}

export interface IPendingProcess {
  status: boolean;
  createAt: string;
  endDate: string;
  serverResponseTime: string;
}
