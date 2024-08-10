export interface IApiResponse<T> {
  success: boolean;
  message: string | null;
  status: number;
  data?: T;
  token?: string;
}
