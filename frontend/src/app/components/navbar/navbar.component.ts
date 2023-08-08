import { Component, OnInit } from '@angular/core';
import { UserServiceService } from 'src/app/services/user-service.service';
import { Router } from '@angular/router';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit{
  isLogged!: boolean;
  username!: string;
  constructor(private userService: UserServiceService, private router: Router, private passMessage: CustomvalidationService){}

  ngOnInit(): void {
    this.userService.status().subscribe((statusLogged: boolean) => {
      this.isLogged = statusLogged;
    })

    const user: any = localStorage.getItem('user')
    this.username = JSON.parse(user).user;
  }

  onLogout(){
    this.userService.logout().subscribe((res: any) => {
      console.log(res.message);
      localStorage.removeItem('access_token')
      localStorage.removeItem('user_type')
      this.userService.changeStatus(false);
      this.passMessage.setMessage(res.message);
      this.router.navigate(['/login'])
    })

  }
}
