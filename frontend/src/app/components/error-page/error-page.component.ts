import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-error-page',
  templateUrl: './error-page.component.html',
  styleUrls: ['./error-page.component.scss']
})
export class ErrorPageComponent implements OnInit{
  code = 404;
  errMessage= "Page Not Found";

  constructor(){}
  ngOnInit(): void {
  }

}
