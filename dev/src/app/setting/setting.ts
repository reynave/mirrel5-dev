export class Setting {
    error: number;
    result: {
        account: {
            email: string;
        }
        embed: {
            embed_code: string;
            header_code: string;
        }
        smtp: {
            smtp_host: string;
            smtp_pass: string;
            smtp_port: string;
            smtp_to: string;
            smtp_user: string;
            subject: string;
        }
    }
}
