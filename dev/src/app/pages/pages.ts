export class PagesEdit {

  constructor(
    public id_pages: string,
    public name: string,
    public url: string,
    public href: string,
    public href_target_blank: boolean,
    public img: string,
    public title: string,
    public metadata_description: string,
    public metadata_keywords: string,
    public post: boolean,
    public themes: string,
  ) { }
}
export class Pages {
  benchmark: [
    {
      id: string;
      name: string;
    }
  ]
  pages: [
    {
      child: boolean;
      children: [
        {
          child: boolean;
          id: number;
          id_pages:  number;
          lock: boolean;
          name: string;
          status: string;
          url: string;
        }
      ]
      id: number;
      id_pages: number;
      lock: boolean;
      name: string;
      status: string;
      url: string;
    }
  ]
}

export class PagesDetail {

  error: 0;
  result: {
    data:
    {
      id: string;
      id_pages: string;
      name: string;
      parent_name :string;
      url: string;
      themes: string;
      href: string;
      href_target_blank: boolean;
      img: string;
      title: string;
      metadata_description: string;
      metadata_keywords: string;
      post: boolean;
    }
    themes: any;
  }

}
