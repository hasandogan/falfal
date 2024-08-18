import { IFortune } from './IFortune';

export interface IDashboardResponse {
  pendingProcess: IPendingProcess;
  fortunes: IFortune[];
}

export interface IPendingProcess {
  status: boolean;
  createAt: string | null;
  endDate: string | null;
  serverResponseTime: string | null;
}
