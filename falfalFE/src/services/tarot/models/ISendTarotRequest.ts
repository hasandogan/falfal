export interface ISendTarotRequest {
  selectedTarotsCards: IKeyValue[];
  question: string;
}

interface IKeyValue {
  key: number;
  value: boolean;
}
