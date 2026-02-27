export class ContentEdit {
    
  constructor( 
    public id_pages: number,  
    public name: string,  
    public h1: string,  
    public h2: string,  
    public h3: string,   
    public img: string,
    public title: string,
    public metadata_description: string,
    public metadata_keywords: string,  
    public url:string,
    public status:string,
    
  ) { }

}

export class ContentList {

  error: number;
  order : {
    no: string;
    total: string;
  }
  result: [
    {
      id: string;
      input_date: string;
      name: string;
      pages: string;
      status: string;
      url: string;
    }
  ]
  

}
