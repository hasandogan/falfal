export interface ISendTarotRequest {
  selectedTarotsCards: IKeyValue[];
  question: string;
}

export interface IKeyValue {
  key: number;
  value: boolean;
}
