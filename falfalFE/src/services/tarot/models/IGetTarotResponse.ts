import { IKeyValue } from './ISendTarotRequest';

export interface IGetTarotResponse {
  id: number;
  selectedCards: IKeyValue[];
  message: string;
}
