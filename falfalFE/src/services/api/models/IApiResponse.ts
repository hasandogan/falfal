export interface IApiResponse<T> {
  success: boolean;
  message: string | null;
  statatus: number;
  data?: T;
}
