import { Component, OnInit } from '@angular/core';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';

@Component({
  selector: 'app-mail-sent',
  templateUrl: './mail-sent.component.html',
  styleUrls: ['./mail-sent.component.scss']
})
export class MailSentComponent implements OnInit{
  message:any;
  constructor(private sharedMessage: CustomvalidationService){}

  ngOnInit(): void {
    this.message = this.sharedMessage.getMessage();
  }


}
