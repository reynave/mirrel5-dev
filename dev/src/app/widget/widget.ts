export class Widget {
}

export class WidgetSection {
    error: 0;
    result: {
        data:
        { 
            h1: string;
            id: string; 
            img: string;
            input_date: string;
        } 
    }
}

export class WidgetDetail {
    error: 0;
    result: {
        data:
        {
            content: string;
            h1: string;
            h2: string;
            h3: string;
            h4: string;
            href: string;
            id: string;
            id_content: string;
            img: string;
            section: string;
        }
    }

}

export class WidgetEdit {
    constructor(
        public h1: string,
        public h2: string,
        public h3: string,
        public h4: string,
        public content: string,
        public img: string,
        public href: string,
        
    ) { }
}