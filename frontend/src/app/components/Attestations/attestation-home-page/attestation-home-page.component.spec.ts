import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttestationHomePageComponent } from './attestation-home-page.component';

describe('AttestationHomePageComponent', () => {
  let component: AttestationHomePageComponent;
  let fixture: ComponentFixture<AttestationHomePageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AttestationHomePageComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AttestationHomePageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
